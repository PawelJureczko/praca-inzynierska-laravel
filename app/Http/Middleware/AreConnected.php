<?php

namespace App\Http\Middleware;

use App\Repositories\UsersRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AreConnected
{

    public function __construct(private readonly UsersRepository $usersRepository)
    {
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = auth()->user()->role;
        $teacherId = $role === 'teacher' ? auth()->user()->id : $request->route('id');
        $studentId=  $role === 'teacher' ? $request->route('id') : auth()->user()->id;
        $connection = $this->usersRepository->doesConnectionExist($teacherId, $studentId);
        if ($connection) {
            return $next($request);
        }
        abort(403, 'Unauthorized'); // Przypadek braku dostępu
    }
}
