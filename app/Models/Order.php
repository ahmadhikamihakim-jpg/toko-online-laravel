<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Order extends Model {
    protected $fillable = ['user_id','code','recipient_name','phone','address','payment_method','status','total','snap_token','payment_status','payment_type','paid_at'];
    protected function casts(): array { return ['total'=>'integer','paid_at'=>'datetime']; }
    public const STATUSES = ['pending'=>'Menunggu Pembayaran','paid'=>'Sudah Dibayar','shipped'=>'Dikirim','completed'=>'Selesai','cancelled'=>'Dibatalkan'];
    public const PAYMENTS = ['transfer'=>'Transfer Bank','cod'=>'Bayar di Tempat (COD)','midtrans'=>'Pembayaran Online (Midtrans)'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
    public function statusLabel(): string { return self::STATUSES[$this->status] ?? $this->status; }
    public function paymentLabel(): string { return self::PAYMENTS[$this->payment_method] ?? $this->payment_method; }
}
