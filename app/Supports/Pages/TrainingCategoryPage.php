<?php

namespace App\Supports\Pages;

class TrainingCategoryPage
{
    public static function index(): array
    {
        return [
            'title' => 'Kategori Pelatihan',
            'subtitle' => 'Kelola seluruh kategori pelatihan yang tersedia.',

            'banner' => [
                'title' => 'Manajemen Kategori Pelatihan',
                'subtitle' => 'Tambah, ubah, dan kelola kategori pelatihan untuk mendukung proses pembelajaran.',
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'title' => 'Tambah Kategori Pelatihan',
            'subtitle' => 'Tambahkan kategori pelatihan baru.',
            'method' => 'POST',
            'action' => route('training.categories.store'),
        ];
    }

    public static function edit($trainingCategory): array
    {
        return [
            'title' => 'Perbarui Kategori Pelatihan',
            'subtitle' => 'Perbarui informasi kategori pelatihan.',
            'method' => 'PUT',
            'action' => route('training.categories.update', $trainingCategory),
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
                'label' => 'Kategori Pelatihan',
                'href' => route('training.categories.index'),
            ],
            [
                'label' => 'Perbarui Kategori Pelatihan',
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
                'label' => 'Kategori Pelatihan',
                'href' => route('training.categories.index'),
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
