<?php
class Admin extends controller{
public function show(Setor $setor): View
{
    Gate::authorize('view', $setor);

    return view('setores.show', [
        'setor' => $setor->load([
            'administrador',
            'funcionarios.cargo',
        ]),
    ]);
}
}