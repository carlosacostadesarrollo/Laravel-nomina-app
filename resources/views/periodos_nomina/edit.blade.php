<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Período de Nómina: ' . $periodo->anio . '/' . $periodo->mes) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

                    {{-- Formulario de Edición --}}
                    {{-- El método es POST, pero Laravel utiliza @method('PUT') para las actualizaciones --}}
                    <form method="POST" action="{{ route('periodos_nomina.update', $periodo->id) }}">
                        @csrf
                        @method('PUT')

                        <h3 class="text-lg font-bold mb-6 text-indigo-700 border-b pb-2">:: DATOS DEL PERÍODO</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Campo Año --}}
                            <div>
                                <label for="anio" class="block font-medium text-sm text-gray-700">Año *</label>
                                <select id="anio" name="anio" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    @php
                                        $currentYear = date('Y');
                                        for ($i = $currentYear; $i <= $currentYear + 2; $i++) {
                                            $selected = ($i == old('anio', $periodo->anio)) ? 'selected' : '';
                                            echo "<option value='{$i}' {$selected}>{$i}</option>";
                                        }
                                    @endphp
                                </select>
                                @error('anio') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Campo Mes --}}
                            <div>
                                <label for="mes" class="block font-medium text-sm text-gray-700">Mes *</label>
                                <select id="mes" name="mes" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    @php
                                        $meses = [
                                            '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
                                            '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
                                            '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                                        ];
                                    @endphp
                                    <option value="">Seleccione...</option>
                                    @foreach ($meses as $num => $nombre)
                                        <option value="{{ $num }}" {{ $num == old('mes', $periodo->mes) ? 'selected' : '' }}>
                                            {{ $nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('mes') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Campo Fecha Inicio --}}
                            <div>
                                <label for="fecha_inicio" class="block font-medium text-sm text-gray-700">Fecha Inicio *</label>
                                  <input id="fecha_inicio" name="fecha_inicio" type="date" 
                                        value="{{ old('fecha_inicio', \Carbon\Carbon::parse($periodo->fecha_inicio)->format('Y-m-d')) }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @error('fecha_inicio') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Campo Fecha Fin --}}
                            <div>
                                <label for="fecha_fin" class="block font-medium text-sm text-gray-700">Fecha Fin *</label>
                                            <input id="fecha_fin" name="fecha_fin" type="date" 
                                        value="{{ old('fecha_fin', \Carbon\Carbon::parse($periodo->fecha_fin)->format('Y-m-d')) }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @error('fecha_fin') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Campo Estado --}}
                            <div class="md:col-span-2">
                                <label for="estado" class="block font-medium text-sm text-gray-700">Estado *</label>
                                <select id="estado" name="estado" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    @php
                                        $estados = ['Abierto', 'Cerrado', 'Pagado']; // Basado en tu estructura de DB
                                    @endphp
                                    <option value="">Seleccione...</option>
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado }}" {{ $estado == old('estado', $periodo->estado) ? 'selected' : '' }}>
                                            {{ $estado }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estado') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                        </div> {{-- Fin del grid --}}

                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('periodos_nomina.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150 mr-4">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                                Actualizar Período
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>