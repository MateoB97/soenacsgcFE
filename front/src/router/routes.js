
const routes = [
  {
    path: '/',
    meta: {
      auth: false
    },
    component: () => import('layouts/MyLayout.vue'),
    children: [
      { path: '', name: 'home', component: () => import('pages/Index.vue') },
      { path: 'register', name: 'register', component: () => import('pages/Register.vue') },
      { path: 'changepass', name: 'changepass', component: () => import('pages/user/cambiarPass.vue'), meta: { auth: true } }
    ]
  },
  {
    path: '/enterprises',
    component: () => import('layouts/MyLayout.vue'),
    children: [
      { path: '', component: () => import('pages/enterprise/enterprisePage.vue') },
      { path: 'adminEnterprise', component: () => import('pages/enterprise/enterpriseAdmin.vue') }
    ]
  },
  {
    path: '/generals',
    component: () => import('layouts/MyLayout.vue'),
    children: [
      { path: '', component: () => import('pages/generales/generalsPage.vue') }
    ]
  },
  {
    path: '/usuarios',
    component: () => import('layouts/MyLayout.vue'),
    children: [
      { path: 'categorias-permisos', meta: { permisoRequerido: '37' }, component: () => import('pages/user/CategoriasPermisos.vue') },
      { path: 'permisos', meta: { permisoRequerido: '38' }, component: () => import('pages/user/PermisosUsuarios.vue') },
      { path: 'roles', meta: { permisoRequerido: '39' }, component: () => import('pages/user/RolesUsuarios.vue') },
      { path: 'asociar-permisos-rol', meta: { permisoRequerido: '40' }, component: () => import('pages/user/AsociarPermisosRol.vue') }
    ]
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('pages/Login.vue'),
    meta: {
      auth: false
    }
  }
]

// Always leave this as last one
if (process.env.MODE !== 'ssr') {
  routes.push({
    path: '*',
    component: () => import('pages/Error404.vue')
  })
}

export default routes
