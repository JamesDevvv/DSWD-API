<?php

namespace App\Models\Core;

use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
  protected $connection = 'mysql';
}
