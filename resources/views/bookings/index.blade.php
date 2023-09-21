<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Id
                        </th>

                        <th scope="col" class="px-6 py-3">
                            Timezone
                        </th>

                        <th scope="col" class="px-6 py-3">
                            User
                        </th>

                        <th scope="col" class="px-6 py-3">
                            Start date
                        </th>

                        <th scope="col" class="px-6 py-3">
                            End date
                        </th>
                        <th scope="col" class="px-6 py-3">
                           Timestamp start
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bookings as $booking)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $booking->id }}</td>
                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $booking->user->timezone }}</td>
                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $booking->user->name }}</td>

                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ UTCtoUserDateTime($booking->start) }}
                            </td>

                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ UTCtoUserDateTime($booking->end) }}
                            </td>

                            <td class="border px-4 py-2 text-center">
                                {{ UTCtoUserDateTime($booking->timestamp) }} <br>
                                <a href="{{route('bookings.edit', $booking->id)}}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
