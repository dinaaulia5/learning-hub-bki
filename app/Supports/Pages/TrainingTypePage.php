<?php

namespace App\Supports\Pages;

class TrainingTypePage
{
    public static function index(): array
    {
        return [
            'title' => 'Tipe Pelatihan',
            'subtitle' => 'Kelola seluruh Tipe pelatihan yang tersedia.',

            'banner' => [
                'title' => 'Manajemen Tipe Pelatihan',
                'subtitle' => 'Tambah, ubah, dan kelola Tipe pelatihan untuk mendukung proses pembelajaran.',
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'title' => 'Tambah Tipe Pelatihan',
            'subtitle' => 'Tambahkan Tipe pelatihan baru.',
            'method' => 'POST',
            'action' => route('training.types.store'),
        ];
    }

    public static function edit($trainingCategory): array
    {
        return [
            'title' => 'Perbarui Tipe Pelatihan',
            'subtitle' => 'Perbarui informasi Tipe pelatihan.',
            'method' => 'PUT',
            'action' => route('training.types.update', $trainingCategory),
        ];
    }

    public static function editBreadcrumbs(): array
    {
        return [
            [
                'label' => 'Dashboard',
                'href' => route('dashboard'),
            ],
            [
                'label' => 'Tipe Pelatihan',
                'href' => route('training.types.index'),
            ],
            [
                'label' => 'Perbarui Tipe Pelatihan',
            ],
        ];
    }

    public static function breadcrumbs(?string $current = null): array
    {
        $items = [
            [
                'label' => 'Dashboard',
                'href' => route('dashboard'),
            ],
            [
                'label' => 'Tipe Pelatihan',
                'href' => route('training.types.index'),
            ],
        ];

        if ($current) {
            $items[] = [
                'label' => $current,
            ];
        } else {
            unset($items[1]['href']);
        }

        return $items;
    }
}
