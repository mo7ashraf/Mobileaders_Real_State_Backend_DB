<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
  public $timestamps = false;
  protected $table = 'Favorite';
  protected $fillable = ['userId','listingId'];

  public $incrementing = false;
  protected $keyType = 'string';

  public function getKeyName()
  {
    return 'composite';
  }

  public function getKey()
  {
    return (string) ($this->userId . '|' . $this->listingId);
  }
}
