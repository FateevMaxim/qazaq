@if(isset($config->address)) @section( 'chinaaddress', $config->address ) @endif
@if(isset($config->title_text)) @section( 'title_text', $config->title_text ) @endif
@if(isset($config->address_two)) @section( 'address_two', $config->address_two ) @endif
<x-app-layout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                @if(session()->has('message'))
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                        <span class="font-medium">{{ session()->get('message') }}
                    </div>
                @endif
                    <div class="grid md:grid-cols-2 grid-cols-1 gap-3 h-22 pl-6 pr-6 pb-4">
                        <div class="grid grid round_border min_height grid-cols-1 p-4 relative">
                            <div>
                                 <span>
                                Пункт приёма
                                </span>
                                <h3>Китай</h3>
                            </div>
                            <div class="absolute p-4 bottom-0">
                                <span>Количество зарегистрированных трек кодов за сегодня</span>
                                <h3>{{ $count }}</h3>
                            </div>

                        </div>
                        <div class="grid grid-cols-1 p-4 min_height round_border relative">
                            <form method="POST" action="{{ route('china-product') }}" id="searchForm">
                                <div class="grid mx-auto">
                                    <label>Введите трек код</label>
                                    <input type="text" name="track_code" id="track_code">

                                    <a class="bg-blue-500 hover:bg-blue-700 text-white text-center font-bold mt-8 py-2 px-4 rounded" id="generateCode" style="display: none;">Сгенерировать штрих код</a>
                                    <div id="content-to-print" class="mt-4">
                                    </div>
                                    <div class="mt-8">
                                        <button onclick="printBlock('content-to-print')" class="bg-blue-500 hover:bg-blue-700 text-white text-center font-bold py-2 px-4 rounded" id="printButton" style="display: none;">Добавить и распечатать</button>
                                    </div>

                                </div>
                                <div id="track" class="mt-8">
                                    <span>Счётчик</span>

                                    <div x-data="{ count: 0 }">
                                        <h1 id="count"></h1>
                                    </div>
                                </div>
                                <div class="absolute w-full bottom-0 p-4">

                                        <div>
                                            <div>
                                                @csrf
                                                <x-primary-button class="mx-auto w-full">
                                                    {{ __('Загрузить') }}
                                                </x-primary-button>
                                            </div>
                                        </div>

                                </div>
                            </form>
                        </div>

                        <script>

                            let code = "";
                            var number = 1;

                            document.addEventListener('keypress', e => {
                                if (e.key === "Enter") {
                                    $('#track_codes_list').append('<h2>'+number+'. '+code+'</h2>');
                                    $('#clear_track_codes').append(code+'\r\n');
                                    $("#count").text(number);
                                    number++;
                                    code = "";
                                } else {
                                    if(e.code[0] === "D"){
                                        code += e.code[5]
                                        return
                                    }
                                    code += e.code[3];
                                }
                            });

                            /* прикрепить событие submit к форме */
                            $("#searchForm").submit(function(event) {
                                /* отключение стандартной отправки формы */
                                event.preventDefault();

                                /* собираем данные с элементов страницы: */
                                var $form = $( this ),
                                    track_codes = $("#clear_track_codes").html();
                                url = $form.attr( 'action' );

                                /* отправляем данные методом POST */
                                $.post( url, { track_codes: track_codes } )
                                    .done(function( data ) {
                                        location.reload();
                                    });

                            });

                            /* прикрепить событие submit к форме */
                            $("#clear").click(function(event) {
                                /* отключение стандартной отправки формы */
                                event.preventDefault();

                                $("#track_codes_list").html('');
                                $("#clear_track_codes").html('');
                                number = 1;
                                $("#count").text('0');

                            });

                        </script>
                        <script>
                            $(document).ready(function() {

                                $('#track_code').on('input', function() {
                                    var trackCode = $(this).val();
                                    if (trackCode.length > 0) {
                                        $('#generateCode').show();
                                    } else {
                                        $('#generateCode').hide();
                                    }
                                });


                                $('#generateCode').click(function() {
                                    var trackCode = $('#track_code').val();

                                    $.ajax({
                                        url: '/generate-bar-code',
                                        type: 'POST',
                                        data: {
                                            _token: '{{ csrf_token() }}', // Добавляем CSRF токен
                                            track_code: trackCode
                                        },
                                        success: function(response) {
                                            var barcodeImage = '<img src="data:image/png;base64,' + response.barcode + '"/><br />';
                                            $('#content-to-print').html(barcodeImage + trackCode);
                                            $('#printButton').show();
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Ошибка при генерации штрих-кода:', error);
                                        }
                                    });
                                });

                                $('#printButton').click(function() {
                                    var trackCode = $('#track_code').val();

                                    $.ajax({
                                        url: '/china-product',
                                        type: 'POST',
                                        data: {
                                            _token: '{{ csrf_token() }}', // Добавляем CSRF токен
                                            track_code: trackCode
                                        },
                                        success: function(response) {
                                            var barcodeImage = '<img src="data:image/png;base64,' + response.barcode + '"/>';
                                            trackCode.val('');
                                            $('#printButton').hide();
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Ошибка при генерации штрих-кода:', error);
                                        }
                                    });
                                });

                            });
                        </script>
                    </div>
            </div>
        </div>
</x-app-layout>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.4/datepicker.min.js"></script>
<script src="{{ asset('js/print.js') }}"></script>
