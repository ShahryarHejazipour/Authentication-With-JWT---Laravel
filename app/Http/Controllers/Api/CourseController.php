<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    /*
     * COURSE ENROLLMENT API - POST
     */
    public function courseEnrollment(Request $request)
    {

        /*
         * Validation
         * in this section we dont need valid "user_id", because we will access this field via "Middleware".
         * BUT
         * what middleware?
         * ANSWER: the middleware that we passed in Route::group for protected this Rout group in api.php file -- ['middleware'=>['auth:api'] --
         */

        $request->validate([

            "title" => "required",
            "description" => "required",
            "total_videos" => "required"

        ]);

        /*
         * Create Course Object
         */

        $course = new Course();

        $course->user_id = auth()->user()->id; // user_id value is access via " auth()" helper function in ['middleware'=>['auth:api']]

        $course->title = $request->title;
        $course->description = $request->description;
        $course->total_videos = $request->total_videos;

        $course->save();

        /*
         * Send Response
         */
        return response()->json([
            'status' => 1,
            'message' => 'Course enrolled successfully'
        ]);


    }

    /*
     * TOTAL COURSE ENROLLMENT API - GET
     */
    public function totalCourses()
    {

        $id = auth()->user()->id;

        $courses = User::find($id)->courses;

        return response()->json([
            "status" => 1,
            "message" => "Total Courses enrolled",
            "data" => $courses
        ]);

    }

    /*
     * DELETE COURSE API - GET
     */
    public function deleteCourse($id)
    {

        // user id
        // course id
        // courses table
        $user_id = auth()->user()->id;

        if (Course::where([
            "id" => $id,
            "user_id" => $user_id
        ])->exists()) {

            $course = Course::find($id);

            $course->delete();

            return response()->json([
                "status" => 1,
                "message" => "Course deleted successfully"
            ]);
        } else {

            return response()->json([
                "status" => 0,
                "message" => "Course not found"
            ]);
        }

    }
}
