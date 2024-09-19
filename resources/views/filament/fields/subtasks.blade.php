@php
    $subtasks = [
           ['name' => "Permettre à un employé de se logger avec un NIP plutôt qu'un email (via l'API)", 'status' => 'Not started'],
           ['name' => 'Setup https://filamentphp.com/plugins/rupadana-api-service', 'status' => 'Not started'],
           ['name' => 'Générer les APIs pour les ressources nécessaires', 'status' => 'Not started'],
           ['name' => 'Générer et envoyer par courriel le rapport de conformité', 'status' => 'Not started'],
           ['name' => "Enregistrer la commande suite à l'expédition", 'status' => 'Not started'],
           ['name' => 'Appels API en surplus (Imprévus)', 'status' => 'Not started']
       ];

@endphp

<div class="p-4 bg-gray-900 text-white">
    <h2 class="font-semibold text-xl mb-4">Subtasks</h2>
    <ul class="space-y-4">
        @foreach($subtasks as $subtask)
            <li class="flex items-center">
                <span class="w-5 h-5 flex items-center justify-center bg-gray-600 rounded-full mr-2">
                  <x-filament::icon
                      icon="heroicon-m-chevron-right"

                    />
                </span>
                <span class="flex-1">{{ $subtask['name'] }}</span>
                <span class="px-3 py-1 text-sm bg-{{ $subtask['status'] == 'Not started' ? 'blue' : 'green' }}-500 rounded-full">
                    {{ ucfirst($subtask['status']) }}
                </span>
            </li>
        @endforeach
    </ul>
</div>
