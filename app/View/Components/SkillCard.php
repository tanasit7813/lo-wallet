<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SkillCard extends Component
{
    public string $boxClasses;
    public string $titleClasses;
    public string $countClasses;

    public function __construct(
        public string $title,
        public int $count,
        public string $colorName
    ) {
        $isActive = $this->count > 0;

        if ($isActive) {
            // ใช้ match เพื่อเลือกชุดคลาสทั้งหมดที่เป็น string แบบเต็มๆ
            $colorClasses = match ($this->colorName) {
                'sky'     => ['bg' => 'bg-sky-100 dark:bg-sky-900', 'title' => 'text-sky-600 dark:text-sky-300', 'count' => 'text-sky-800 dark:text-sky-100'],
                'emerald' => ['bg' => 'bg-emerald-100 dark:bg-emerald-900', 'title' => 'text-emerald-600 dark:text-emerald-300', 'count' => 'text-emerald-800 dark:text-emerald-100'],
                'amber'   => ['bg' => 'bg-amber-100 dark:bg-amber-900', 'title' => 'text-amber-600 dark:text-amber-300', 'count' => 'text-amber-800 dark:text-amber-100'],
                'rose'    => ['bg' => 'bg-rose-100 dark:bg-rose-900', 'title' => 'text-rose-600 dark:text-rose-300', 'count' => 'text-rose-800 dark:text-rose-100'],
                'indigo'  => ['bg' => 'bg-indigo-100 dark:bg-indigo-900', 'title' => 'text-indigo-600 dark:text-indigo-300', 'count' => 'text-indigo-800 dark:text-indigo-100'],
                'purple'  => ['bg' => 'bg-purple-100 dark:bg-purple-900', 'title' => 'text-purple-600 dark:text-purple-300', 'count' => 'text-purple-800 dark:text-purple-100'],
                default   => ['bg' => 'bg-gray-100 dark:bg-gray-900', 'title' => 'text-gray-600 dark:text-gray-300', 'count' => 'text-gray-800 dark:text-gray-100'],
            };

            // นำค่าที่ได้มาต่อกับคลาสส่วนกลาง
            $this->boxClasses = $colorClasses['bg'] . ' hover:shadow-xl hover:-translate-y-1';
            $this->titleClasses = $colorClasses['title'];
            $this->countClasses = $colorClasses['count'];
        } else {
            $this->boxClasses = 'bg-gray-100 dark:bg-gray-800 opacity-60';
            $this->titleClasses = 'text-gray-500';
            $this->countClasses = 'text-gray-400';
        }
    }

    public function render()
    {
        return view('components.skill-card');
    }
}
