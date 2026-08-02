<?php

class ListarSetoresQuery
{
    public function execute()
    {
        return Setor::query()
            ->with('administrador')
            ->orderBy('name')
            ->paginate(15);
    }
}