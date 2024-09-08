<?php

namespace App\Models;

use App\Traits\CanGetNamesStatically;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use CanGetNamesStatically;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public const NAME = 'name';

    public const ROLE = 'role';

    public const PHONE = 'phone';

    public const IS_DEFAULT = 'is_default';

    public const EXTENSION = 'extension';

    public const CELL_PHONE = 'cell_phone';

    public const PREFER_SEND_MODE = 'prefer_send_mode';

    public const FAX = 'fax';

    public const EMAIL = 'email';

    public const NOTES = 'notes';

    public const CONTACTABLE_ID = 'contactable_id';

    public const CONTACTABLE_TYPE = 'contactable_type';

    public const FULL_CONTACT = 'full_contact';

    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return Attribute<string, never> */
    protected function fullContact(): Attribute
    {
        $status = $this->deleted_at ? ' (désactivée)' : '';

        return Attribute::make(
            get: fn (): string => sprintf('%s, %s, %s%s', $this->{self::NAME}, $this->{self::PHONE}, $this->{self::EMAIL}, $status)
        );
    }
}
