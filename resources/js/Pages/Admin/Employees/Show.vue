<template>
  <AdminLayout>
    <template #header>
      <div>
        <Breadcrumb :crumbs="[{ label: 'Employees', url: route('admin.employees.index') }, { label: employee.name }]" />
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white mt-2">{{ employee.name }}</h1>
      </div>
    </template>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="glass-card p-5 text-center">
        <Avatar :name="employee.name" size="2xl" class="mx-auto" />
        <h3 class="mt-3 font-semibold text-gray-900 dark:text-white">{{ employee.name }}</h3>
        <p class="text-sm text-gray-500">{{ employee.role }}</p>
        <p class="text-sm text-gray-500">{{ employee.department }}</p>
        <div class="mt-4 space-y-2 text-sm text-left">
          <div><span class="text-gray-500">Email:</span> {{ employee.email }}</div>
          <div><span class="text-gray-500">Phone:</span> {{ employee.phone }}</div>
          <div><span class="text-gray-500">Joined:</span> {{ employee.joined_date }}</div>
        </div>
      </div>

      <div class="lg:col-span-2 space-y-6">
        <div class="glass-card p-5">
          <SectionHeader title="Performance Overview" size="md" />
          <div class="grid grid-cols-3 gap-4 mt-4">
            <StatCard label="Tasks Completed" :value="employee.tasks_completed" icon="CheckCircleIcon" iconBg="bg-emerald-50" iconColor="text-emerald-600" />
            <StatCard label="Attendance" :value="employee.attendance + '%'" icon="UserIcon" iconBg="bg-blue-50" iconColor="text-blue-600" />
            <StatCard label="Projects" :value="employee.projects" icon="CubeIcon" iconBg="bg-purple-50" iconColor="text-purple-600" />
          </div>
        </div>

        <div class="glass-card p-5">
          <SectionHeader title="Recent Activity" size="md" />
          <div class="mt-4 space-y-3">
            <div v-for="act in employee.recentActivity" :key="act.id" class="flex items-center gap-3 text-sm">
              <div class="w-2 h-2 rounded-full bg-brand-500" />
              <span class="text-gray-600 dark:text-gray-400">{{ act.action }}</span>
              <span class="text-xs text-gray-400 ml-auto">{{ act.time }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Breadcrumb from '@/Components/Shared/UI/Breadcrumb.vue'
import Avatar from '@/Components/Shared/UI/Avatar.vue'
import SectionHeader from '@/Components/Shared/UI/SectionHeader.vue'
import StatCard from '@/Components/Shared/UI/StatCard.vue'

const employee = {
  id: 1, name: 'Alice Johnson', email: 'alice@company.com', phone: '+1 555-1234',
  department: 'Engineering', role: 'Senior Developer', joined_date: 'Jan 2023',
  tasks_completed: 45, attendance: 97, projects: 8,
  recentActivity: [
    { id: 1, action: 'Completed task "Deploy v2.0"', time: '2 hours ago' },
    { id: 2, action: 'Attended sprint planning', time: '5 hours ago' },
    { id: 3, action: 'Submitted code review', time: '1 day ago' },
  ],
}
</script>
