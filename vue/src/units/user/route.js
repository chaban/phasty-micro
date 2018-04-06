import Layout from '@/views/layout/Layout'

const route = {
  path: '/Users',
  component: Layout,
  redirect: '/Users/index',
  name: 'Users',
  meta: { title: 'Users', icon: 'example' },
  children: [
    {
      path: 'index',
      name: 'All Users',
      component: require('@/units/user/views/index.vue').default,
      meta: { title: 'Table', icon: 'table' }
    },
    {
      path: 'create',
      name: 'Create user',
      component: require('@/units/user/views/create.vue').default,
      meta: { title: 'New', icon: 'form' }
    },
    {
      path: 'edit/:id',
      name: 'Edit user',
      hidden: true,
      component: require('@/units/user/views/edit.vue').default,
      meta: { title: 'Edit', icon: 'form' }
    }
  ]
}
export default route
