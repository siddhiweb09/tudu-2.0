<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Team extends Model
{
    protected $fillable = ['team_name', 'team_code', 'team_leader', 'team_members', 'team_visibilty', 'team_avatar', 'created_by', 'created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($team) {
            $team->team_code = self::generateTeamCode();
        });
    }

    public static function generateTeamCode()
    {
        $prefix = 'TEAM-';
        $datePart = now()->format('Ymd');
        $randomPart = Str::upper(Str::random(4));

        do {
            $teamId = $prefix . $datePart . '-' . $randomPart;
            $randomPart = Str::upper(Str::random(4));
        } while (self::where('team_code', $teamId)->exists());

        return $teamId;
    }
}
