<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
 
class CommentsReportController extends Controller
{
    /**
     * Show all of the users for the application.
     *
     * @return Response
     */
    public function index()
    {
        $candy = DB::table('comments_report')->where('comment_type', 'candy')->paginate(5, ['*'], 'candy');
        $callme = DB::table('comments_report')->where('comment_type', 'callme')->paginate(5, ['*'], 'callme');
        $referral = DB::table('comments_report')->where('comment_type', 'referral')->paginate(5, ['*'], 'referral');
        $signature = DB::table('comments_report')->where('comment_type', 'signature')->paginate(5, ['*'], 'signature');
        $misc = DB::table('comments_report')->where('comment_type', 'misc')->paginate(5, ['*'], 'misc');
 
        return view('comments', ['candy' => $candy, 'callme' => $callme, 'referral' => $referral, 'signature' => $signature, 'misc' => $misc ]);
    }
}