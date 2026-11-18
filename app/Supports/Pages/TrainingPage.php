<?php

namespace App\Supports\Pages;

class TrainingPage
{
    public static function index(): array
    {
        return [
            'title' => 'Pelatihan',
            'subtitle' => 'Kelola seluruh pelatihan yang tersedia.',

            'banner' => [
                'title' => 'Manajemen Pelatihan',
                'subtitle' => 'Tambah, ubah, dan kelola pelatihan untuk mendukung proses pembelajaran.',
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'title' => 'Tambah Pelatihan',
            'subtitle' => 'Tambahkan pelatihan baru.',
            'method' => 'POST',
            'action' => route('trainings.store'),
        ];
    }

    public static function createBreadcrumbs(): array
    {
        return [
            [
                'label' => 'Dashboard',
                'href' => route('dashboard'),
            ],
            [
                'label' => 'Pelatihan',
                'href' => route('trainings.index'),
            ],
            [
                'label' => 'Tambah Pelatihan',
            ],
        ];
    }

    public static function edit($training): array
    {
        return [
            'title' => 'Perbarui Pelatihan',
            'subtitle' => 'Perbarui informasi pelatihan.',
            'method' => 'PUT',
            'action' => route('trainings.update', $training),
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
                'label' => 'Pelatihan',
                'href' => route('trainings.index'),
            ],
            [
                'label' => 'Perbarui Pelatihan',
            ],
        ];
    }

    public static function show($training): array
    {
        return [
            'title' => 'Detail Pelatihan',
            'subtitle' => 'Lihat informasi pelatihan dan kelola modul pembelajaran.',

            'banner' => [
                'title' => $training->title,
                'subtitle' => 'Informasi detail dan kurikulum pembelajaran.',
            ],
        ];
    }

    public static function showBreadcrumbs($training = null): array
    {
        return [
            [
                'label' => 'Dashboard',
                'href' => route('dashboard'),
            ],
            [
                'label' => 'Pelatihan',
                'href' => route('trainings.index'),
            ],
            [
                'label' => $training?->title ?? 'Detail Pelatihan',
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
                'label' => 'Pelatihan',
                'href' => route('trainings.index'),
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
