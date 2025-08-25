<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiResource\Pages;
use App\Filament\Resources\TransaksiResource\RelationManagers;
use App\Models\Transaksi;
use App\Models\Peserta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\View;
use Illuminate\Support\HtmlString;
use Illuminate\Http\Request;
use Filament\Notifications\Notification;
use App\Models\Kas;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Data Keuangan';

    protected static ?string $pluralModelLabel = 'Transaksi';
    
    protected static ?string $modelLabel = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('peserta_id')
                    ->relationship(
                        name: 'peserta',
                        titleAttribute: 'kode_pendaftaran_id'
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Kode Peserta')
                    ->getSearchResultsUsing(
                        fn (string $search): array => Peserta::query()
                            ->where('nama', 'like', "%{$search}%")
                            ->limit(50)
                            ->pluck('nama', 'id')
                            ->toArray()
                    )
                  ,
                Forms\Components\TextInput::make('tahun_masuk')
                    ->disabled()
                     ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $peserta = Peserta::find($state);
                            if ($peserta) {
                                $set('tahun_masuk', $peserta->tahun_ajaran_masuk);
                            }
                        }
                    })
                    ->label('Tahun Masuk'),
                Forms\Components\TextInput::make('total_bayar')
                    ->required()
                    ->numeric()
                    ->label('Total Pembayaran')
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\Select::make('status_pembayaran')
                    ->options([
                        0 => 'Pending',
                        1 => 'Sukses',
                        2 => 'Gagal',
                        3 => 'Expired'
                    ])
                    ->disabled()
                    ->dehydrated()
                    ->label('Status Pembayaran'),
                Forms\Components\TextInput::make('kode_transaksi')
                    ->dehydrated()
                    ->label('Kode Transaksi'),
                Forms\Components\TextInput::make('midtrans_payment_type')
                    ->disabled()
                    ->dehydrated()
                    ->label('Metode Pembayaran'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('peserta.nama')
                    ->sortable()
                    ->searchable()
                    ->label('Nama Peserta'),
                Tables\Columns\TextColumn::make('peserta.kode_pendaftaran_id')
                    ->sortable()
                    ->searchable()
                    ->label('Kode Pendaftaran'),
                Tables\Columns\TextColumn::make('tahun_masuk')
                    ->sortable()
                    ->searchable()
                    ->label('Tahun Masuk'),
                Tables\Columns\TextColumn::make('total_bayar')
                    ->money('IDR')
                    ->sortable()
                    ->label('Total Pembayaran'),
                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'warning',
                        '1' => 'success',
                        '2' => 'danger',
                        '3' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '0' => 'Pending',
                        '1' => 'Sukses',
                        '2' => 'Gagal',
                        '3' => 'Expired',
                    })
                    ->sortable()
                    ->label('Status'),
                Tables\Columns\TextColumn::make('kode_transaksi')
                    ->searchable()
                    ->label('Kode Transaksi'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Tanggal Transaksi'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\Action::make('check_status')
                //     ->label('Cek Status')
                //     ->action(function (Transaksi $record) {
                //         $midtransService = new \App\Services\MidtransService();
                //         $status = $midtransService->checkTransactionStatus($record->midtrans_transaction_id);
                        
                //         if (isset($status->error_message)) {
                //             Notification::make()
                //                 ->title('Gagal Mengecek Status')
                //                 ->body('Error: ' . $status->error_message)
                //                 ->danger()
                //                 ->send();
                //             return;
                //         }
                        
                //         if ($status && isset($status->transaction_status)) {
                //             if ($status->transaction_status == 'expire') {
                //                 $record->update([
                //                     'status_pembayaran' => 3 // Expired
                //                 ]);
                //                 Notification::make()
                //                     ->title('Status transaksi telah diperbarui menjadi Expired')
                //                     ->success()
                //                     ->send();
                //             } elseif ($status->transaction_status == 'pending') {
                //                 Notification::make()
                //                     ->title('Transaksi Belum Dibayar')
                //                     ->body('Status transaksi saat ini masih pending/belum dibayar.')
                //                     ->warning()
                //                     ->send();
                //             } else {
                //                 Notification::make()
                //                     ->title('Status Transaksi: ' . ucfirst($status->transaction_status))
                //                     ->body('Status pembayaran: ' . ucfirst($status->transaction_status))
                //                     ->success()
                //                     ->send();
                //             }
                //         } else {
                //             Notification::make()
                //                 ->title('Gagal Mengecek Status')
                //                 ->body('Tidak dapat mengambil status transaksi dari Midtrans.')
                //                 ->danger()
                //                 ->send();
                //         }
                //     })
                //     ->visible(fn (Transaksi $record) => $record->status_pembayaran == 0),

                Tables\Actions\Action::make('reactivate')
                    ->label('Aktifkan Ulang')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function (Transaksi $record) {
                        $midtransService = new \App\Services\MidtransService();
                        $result = $midtransService->createTransaction($record);
                        $record->update([
                            'status_pembayaran' => 0 // Pending
                        ]);
                        if (is_array($result) && isset($result['redirect_url'])) {
                            redirect()->away($result['redirect_url']);
                        } else {
                            Notification::make()
                                ->title('Gagal Membuat Transaksi')
                                ->body($result['message'] ?? 'Tidak mendapatkan URL pembayaran dari Midtrans.')
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn (Transaksi $record) => $record->status_pembayaran == 3),

                Tables\Actions\Action::make('pay')
                    ->label('Bayar')
                    ->icon('heroicon-o-credit-card')
                    ->action(function (Transaksi $record) {
                        $midtransService = new \App\Services\MidtransService();
                        $result = $midtransService->createTransaction($record);
                        if (is_array($result) && isset($result['redirect_url'])) {
                            redirect()->away($result['redirect_url']);
                        } else {
                            Notification::make()
                                ->title('Gagal Membuat Transaksi')
                                ->body($result['message'] ?? 'Tidak mendapatkan URL pembayaran dari Midtrans.')
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn (Transaksi $record) => $record->status_pembayaran == 0),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public function handlePaymentCallback(Request $request)
    {
        $midtransService = new \App\Services\MidtransService();
        $notification = $midtransService->handleNotification($request);

        $transaction = Transaksi::where('midtrans_transaction_id', $notification->transaction_id)->first();

        if ($transaction) {
            $updateData = [
                'midtrans_payment_type' => $notification->payment_type,
            ];

            if ($notification->transaction_status == 'settlement' || $notification->transaction_status == 'capture') {
                $updateData['status_pembayaran'] = 1; // Sukses
            } elseif (in_array($notification->transaction_status, ['deny', 'cancel', 'failure'])) {
                $updateData['status_pembayaran'] = 2; // Gagal
            } elseif ($notification->transaction_status == 'expire') {
                $updateData['status_pembayaran'] = 3; // Expired
            }

            $transaction->update($updateData);

            // Tambahkan ke kas jika status sukses dan belum ada kas untuk transaksi ini
            if (($notification->transaction_status == 'settlement' || $notification->transaction_status == 'capture') && !\App\Models\Kas::where('transaksi_id', $transaction->id)->exists()) {
                $peserta = $transaction->peserta;
                $tahunAjaranId = $transaction->peserta->kode_pendaftaran->pendaftaran->tahun_ajaran_id;
                \App\Models\Kas::create([
                    'transaksi_id' => $transaction->id,
                    'pagu_anggaran_id' => null,
                    'tahun_ajaran_id' => $tahunAjaranId,
                    'tipe' => 'masuk',
                    'sumber' => 'Transaksi Pendaftaran',
                    'jumlah' => $transaction->total_bayar,
                    'keterangan' => 'Pembayaran dari ' . ($peserta ? $peserta->nama : '-'),
                    'kategori' => 'Pendaftaran',
                    'tanggal' => $transaction->updated_at,
                    'user_id' => null,
                ]);
            }
        }
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status_pembayaran', 0)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
