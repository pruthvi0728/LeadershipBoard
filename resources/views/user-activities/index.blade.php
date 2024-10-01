<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        {{-- Tailwind CSS CDN --}}
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
            <img id="background" class="absolute -left-20 top-0 max-w-[877px]" src="https://laravel.com/assets/img/welcome/background.svg" />
            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    <main class="mt-6">
                        @if($paginatedUsers->isEmpty())
                        <div class="text-center">
                            <h1 class="text-2xl font-bold">No User Found</h1>
                        </div>
                        @else
                            <div class="text-center pb-4">
                                <h1 class="text-2xl font-bold">User Activities</h1>
                            </div>
                            <div class="flex justify-between pb-2">
                                {{-- UserId Filter --}}
                                <div class="flex">
                                    <form action="{{ route('user-activities.index') }}" method="GET">
                                        <div class="flex items">
                                            <input type="text" name="user_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-white" placeholder="Enter User Id" value="{{ request('user_id') }}" />
                                            <input type="hidden" name="sort" value="{{ request('sort') }}" />
                                            <button type="submit" class="px-4 py-2 ml-2 bg-blue-500 text-white rounded-lg hover:bg-blue-400 focus:outline-none focus:bg-blue-400">Filter</button>
                                        </div>
                                    </form>
                                </div>
                                {{-- Dropdown for Sort --}}
                                <div class="flex">
                                    <form action="{{ route('user-activities.index') }}" method="GET">
                                        <div class="flex items">
                                            <select name="sort" class="px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                                                <option value="today" {{ request('sort') == 'today' ? 'selected' : '' }}>Today</option>
                                                <option value="month" {{ request('sort') == 'month' ? 'selected' : '' }}>Month</option>
                                                <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>Year</option>
                                            </select>
                                            <input type="hidden" name="user_id" value="{{ request('user_id') }}" />
                                            <button type="submit" class="px-4 py-2 ml-2 bg-blue-500 text-white rounded-lg hover:bg-blue-400 focus:outline-none focus:bg-blue-400">Sort</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- Table For the listing of user with Id, Name, Points and Rank --}}
                            <table class="w-full bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow-lg overflow-hidden">
                                <thead class="bg-gray-200 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-700 dark:text-gray-300">Id</th>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-700 dark:text-gray-300">Name</th>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-700 dark:text-gray-300">Points</th>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-700 dark:text-gray-300">Rank</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paginatedUsers as $user)
                                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                            <td class="px-4 py-2">{{ $user->id }}</td>
                                            <td class="px-4 py-2">{{ $user->name }}</td>
                                            <td class="px-4 py-2">
                                                @if(request('sort') == 'today')
                                                    {{ $user->pointsToday() }}
                                                @elseif(request('sort') == 'month')
                                                    {{ $user->pointsThisMonth() }}
                                                @elseif(request('sort') == 'year')
                                                    {{ $user->pointsThisYear() }}
                                                @else
                                                    {{ $user->totalPoints() }}
                                                @endif
                                            </td>
                                            <td class="px-4 py-2">
                                                @if(request('sort') == 'today')
                                                    #{{ $user->today_rank }}
                                                @elseif(request('sort') == 'month')
                                                    #{{ $user->monthly_rank }}
                                                @elseif(request('sort') == 'year')
                                                    #{{ $user->yearly_rank }}
                                                @else
                                                    #{{ $user->rank }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $paginatedUsers->links() }}
                        @endif
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
