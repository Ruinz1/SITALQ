// Calendar JavaScript Implementation
class Calendar {
    constructor() {
        this.currentDate = new Date();
        this.selectedDate = null;
        this.events = this.loadEvents();
        this.currentEventDate = null;
        
        this.monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.render();
    }
    
    bindEvents() {
        // Navigation buttons
        document.getElementById('prevMonth').addEventListener('click', () => this.previousMonth());
        document.getElementById('nextMonth').addEventListener('click', () => this.nextMonth());
        document.getElementById('todayBtn').addEventListener('click', () => this.goToToday());
        
        // Modal events
        document.getElementById('closeModal').addEventListener('click', () => this.closeModal());
        document.getElementById('cancelBtn').addEventListener('click', () => this.closeModal());
        document.getElementById('modalOverlay').addEventListener('click', (e) => {
            if (e.target === document.getElementById('modalOverlay')) {
                this.closeModal();
            }
        });
        
        // Form submission
        document.getElementById('eventForm').addEventListener('submit', (e) => this.handleFormSubmit(e));
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => this.handleKeyboard(e));
    }
    
    render() {
        this.updateHeader();
        this.renderCalendar();
        this.updateEventPanel();
    }
    
    updateHeader() {
        const monthYear = document.getElementById('monthYear');
        monthYear.textContent = `${this.monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
    }
    
    renderCalendar() {
        const grid = document.getElementById('calendarGrid');
        grid.innerHTML = '';
        
        const firstDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), 1);
        const lastDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 0);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - firstDay.getDay());
        
        const today = new Date();
        
        for (let i = 0; i < 42; i++) {
            const cellDate = new Date(startDate);
            cellDate.setDate(startDate.getDate() + i);
            
            const dayElement = this.createDayElement(cellDate, today);
            grid.appendChild(dayElement);
        }
    }
    
    createDayElement(date, today) {
        const dayElement = document.createElement('div');
        dayElement.className = 'calendar-day';
        
        const isCurrentMonth = date.getMonth() === this.currentDate.getMonth();
        const isToday = this.isSameDay(date, today);
        const isSelected = this.selectedDate && this.isSameDay(date, this.selectedDate);
        const isWeekend = date.getDay() === 0 || date.getDay() === 6;
        
        // Add appropriate classes
        if (!isCurrentMonth) dayElement.classList.add('other-month');
        if (isToday) dayElement.classList.add('today');
        if (isSelected) dayElement.classList.add('selected');
        if (isWeekend) dayElement.classList.add('weekend');
        
        // Day number
        const dayNumber = document.createElement('div');
        dayNumber.className = 'day-number';
        dayNumber.textContent = date.getDate();
        dayElement.appendChild(dayNumber);
        
        // Add event button
        if (isCurrentMonth) {
            const addBtn = document.createElement('button');
            addBtn.className = 'add-event-btn';
            addBtn.innerHTML = '+';
            addBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.openModal(date);
            });
            dayElement.appendChild(addBtn);
        }
        
        // Events for this day
        const dayEvents = this.getEventsForDay(date);
        dayEvents.forEach(event => {
            const eventElement = document.createElement('div');
            eventElement.className = 'event-item';
            eventElement.textContent = `${event.time} ${event.title}`;
            eventElement.addEventListener('click', (e) => {
                e.stopPropagation();
                this.showEventDetails(date);
            });
            dayElement.appendChild(eventElement);
        });
        
        // Click handler for day selection
        dayElement.addEventListener('click', () => {
            this.selectDate(date);
        });
        
        return dayElement;
    }
    
    selectDate(date) {
        this.selectedDate = new Date(date);
        this.render();
        this.showEventDetails(date);
    }
    
    showEventDetails(date) {
        const panel = document.getElementById('eventPanel');
        const title = document.getElementById('eventPanelTitle');
        const list = document.getElementById('eventList');
        
        const events = this.getEventsForDay(date);
        
        title.textContent = `Acara pada ${date.getDate()} ${this.monthNames[date.getMonth()]} ${date.getFullYear()}:`;
        
        list.innerHTML = '';
        
        if (events.length === 0) {
            const noEvents = document.createElement('p');
            noEvents.textContent = 'Tidak ada acara pada tanggal ini.';
            noEvents.style.color = 'var(--text-muted)';
            list.appendChild(noEvents);
        } else {
            events.forEach(event => {
                const eventCard = this.createEventCard(event, date);
                list.appendChild(eventCard);
            });
        }
        
        panel.style.display = 'block';
    }
    
    createEventCard(event, date) {
        const card = document.createElement('div');
        card.className = 'event-card';
        
        const details = document.createElement('div');
        details.className = 'event-details';
        
        const title = document.createElement('h4');
        title.textContent = event.title;
        details.appendChild(title);
        
        const time = document.createElement('div');
        time.className = 'event-time';
        time.textContent = event.time;
        details.appendChild(time);
        
        if (event.description) {
            const description = document.createElement('div');
            description.className = 'event-description';
            description.textContent = event.description;
            details.appendChild(description);
        }
        
        const deleteBtn = document.createElement('button');
        deleteBtn.className = 'delete-event-btn';
        deleteBtn.textContent = 'Hapus';
        deleteBtn.addEventListener('click', () => {
            this.deleteEvent(event.id, date);
        });
        
        card.appendChild(details);
        card.appendChild(deleteBtn);
        
        return card;
    }
    
    openModal(date) {
        this.currentEventDate = new Date(date);
        const modal = document.getElementById('modalOverlay');
        const form = document.getElementById('eventForm');
        
        form.reset();
        modal.style.display = 'flex';
        
        // Focus on title input
        setTimeout(() => {
            document.getElementById('eventTitle').focus();
        }, 100);
    }
    
    closeModal() {
        const modal = document.getElementById('modalOverlay');
        modal.style.display = 'none';
        this.currentEventDate = null;
    }
    
    handleFormSubmit(e) {
        e.preventDefault();
        
        const title = document.getElementById('eventTitle').value.trim();
        const time = document.getElementById('eventTime').value;
        const description = document.getElementById('eventDescription').value.trim();
        
        if (!title || !time) {
            alert('Judul dan waktu acara harus diisi!');
            return;
        }
        
        const event = {
            id: this.generateId(),
            title,
            time,
            description,
            date: this.formatDate(this.currentEventDate)
        };
        
        this.addEvent(event);
        this.closeModal();
    }
    
    addEvent(event) {
        this.events.push(event);
        this.saveEvents();
        this.render();
        
        // Show success message
        this.showNotification('Acara berhasil ditambahkan!', 'success');
    }
    
    deleteEvent(eventId, date) {
        if (confirm('Apakah Anda yakin ingin menghapus acara ini?')) {
            this.events = this.events.filter(event => event.id !== eventId);
            this.saveEvents();
            this.render();
            this.showEventDetails(date);
            
            // Show success message
            this.showNotification('Acara berhasil dihapus!', 'success');
        }
    }
    
    getEventsForDay(date) {
        const dateString = this.formatDate(date);
        return this.events.filter(event => event.date === dateString);
    }
    
    previousMonth() {
        this.currentDate.setMonth(this.currentDate.getMonth() - 1);
        this.render();
    }
    
    nextMonth() {
        this.currentDate.setMonth(this.currentDate.getMonth() + 1);
        this.render();
    }
    
    goToToday() {
        this.currentDate = new Date();
        this.selectedDate = new Date();
        this.render();
    }
    
    handleKeyboard(e) {
        if (e.key === 'Escape') {
            this.closeModal();
        }
        
        if (!this.selectedDate) return;
        
        let newDate = new Date(this.selectedDate);
        
        switch (e.key) {
            case 'ArrowLeft':
                newDate.setDate(newDate.getDate() - 1);
                break;
            case 'ArrowRight':
                newDate.setDate(newDate.getDate() + 1);
                break;
            case 'ArrowUp':
                newDate.setDate(newDate.getDate() - 7);
                break;
            case 'ArrowDown':
                newDate.setDate(newDate.getDate() + 7);
                break;
            default:
                return;
        }
        
        e.preventDefault();
        
        // Change month if necessary
        if (newDate.getMonth() !== this.currentDate.getMonth()) {
            this.currentDate = new Date(newDate);
        }
        
        this.selectDate(newDate);
    }
    
    // Utility methods
    isSameDay(date1, date2) {
        return date1.getDate() === date2.getDate() &&
               date1.getMonth() === date2.getMonth() &&
               date1.getFullYear() === date2.getFullYear();
    }
    
    formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    generateId() {
        return Math.random().toString(36).substr(2, 9) + Date.now().toString(36);
    }
    
    saveEvents() {
        try {
            localStorage.setItem('calendarEvents', JSON.stringify(this.events));
        } catch (error) {
            console.warn('Could not save events to localStorage:', error);
        }
    }
    
    loadEvents() {
        try {
            const saved = localStorage.getItem('calendarEvents');
            return saved ? JSON.parse(saved) : this.getDefaultEvents();
        } catch (error) {
            console.warn('Could not load events from localStorage:', error);
            return this.getDefaultEvents();
        }
    }
    
    getDefaultEvents() {
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        
        return [
            {
                id: 'default1',
                title: 'Meeting Tim',
                time: '09:00',
                description: 'Meeting rutin dengan tim development',
                date: this.formatDate(today)
            },
            {
                id: 'default2',
                title: 'Presentasi Proyek',
                time: '14:00',
                description: 'Presentasi progress proyek kepada klien',
                date: this.formatDate(tomorrow)
            }
        ];
    }
    
    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? 'var(--success)' : 'var(--primary)'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            z-index: 1001;
            animation: slideIn 0.3s ease-out;
        `;
        notification.textContent = message;
        
        // Add animation styles
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
        
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    updateEventPanel() {
        const panel = document.getElementById('eventPanel');
        if (this.selectedDate) {
            this.showEventDetails(this.selectedDate);
        } else {
            panel.style.display = 'none';
        }
    }
}

// Additional utility functions for potential enhancements
const CalendarUtils = {
    // Export events to JSON
    exportEvents(events) {
        const dataStr = JSON.stringify(events, null, 2);
        const dataBlob = new Blob([dataStr], {type: 'application/json'});
        const url = URL.createObjectURL(dataBlob);
        
        const link = document.createElement('a');
        link.href = url;
        link.download = `calendar-events-${new Date().toISOString().split('T')[0]}.json`;
        link.click();
        
        URL.revokeObjectURL(url);
    },
    
    // Import events from JSON file
    importEvents(file, callback) {
        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const events = JSON.parse(e.target.result);
                callback(events);
            } catch (error) {
                alert('Format file tidak valid!');
            }
        };
        reader.readAsText(file);
    },
    
    // Get events in date range
    getEventsInRange(events, startDate, endDate) {
        return events.filter(event => {
            const eventDate = new Date(event.date);
            return eventDate >= startDate && eventDate <= endDate;
        });
    },
    
    // Search events by title or description
    searchEvents(events, query) {
        const lowerQuery = query.toLowerCase();
        return events.filter(event => 
            event.title.toLowerCase().includes(lowerQuery) ||
            (event.description && event.description.toLowerCase().includes(lowerQuery))
        );
    },
    
    // Get events count per day for current month
    getEventCounts(events, year, month) {
        const counts = {};
        events.forEach(event => {
            const eventDate = new Date(event.date);
            if (eventDate.getFullYear() === year && eventDate.getMonth() === month) {
                const day = eventDate.getDate();
                counts[day] = (counts[day] || 0) + 1;
            }
        });
        return counts;
    }
};

// Initialize calendar when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.calendar = new Calendar();
    
    // Add some development helper functions to window for console access
    window.calendarUtils = CalendarUtils;
    
    console.log('📅 Kalender telah dimuat!');
    console.log('💡 Tips: Gunakan keyboard arrow keys untuk navigasi setelah memilih tanggal');
    console.log('🔧 Akses window.calendar untuk kontrol programmatik');
    console.log('🛠️  Akses window.calendarUtils untuk fungsi utilitas tambahan');
});