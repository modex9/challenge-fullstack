<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id', 'content', 'parent',
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function deleteRecursive($branch_root_id) {
        $children = self::where('parent', $this->id)->get();
        foreach($children as $child) {
            if(self::where('parent', $child->id)->get()->first()) {
                $deleted = $child->deleteRecursive($branch_root_id);
            }
            if(!$child->delete() || (isset($deleted) && !$deleted) )
                return false;
        }
        if($this->id == $branch_root_id)
            $this->delete();
        return true;
    }

    public function getPathToRoot($tree) {
        if($this->parent == 0)
            return false;
        $path = [];
        $current = $this;
        while($current->parent != 0) {
            $path[] = $current->parent;
            $current = Comment::find($current->parent);
        }
        return $path;
    }
}
