import { Routes } from '@angular/router';

import { BaselayoutComponent } from './components/baselayout/baselayout.component';
import { LoginUiComponent } from './pages/users/login-ui/login-ui.component';
import { ErrorPageComponent } from './pages/error-page/error-page.component';

import { AnonGuard } from './services/auth/anon-access.guard';
import { AuthGuard } from './services/auth/auth-access.guard';


export const routes: Routes = [

    { path: '',
      component: BaselayoutComponent,
      canActivate:[AuthGuard([])],
      children: [
        {
          path: '',
          loadChildren: () => import('./pages/consultation/consultation.routes')
            .then(mod => mod.consultationRoutes)
        },

        {
          path : 'profile',
          canActivate: [AuthGuard(['Admin', 'Staff', 'Student'])],
          loadComponent : () => import('./pages/users/profile-page/profile-page.component')
            .then((mod) => mod.ProfilePageComponent)
        },

        { path: 'users',
          canActivate: [AuthGuard(['Admin', 'Staff', 'Student'])],
          loadChildren: () => import('./pages/users/users.routes')
            .then((mod) => mod.usersRoutes)
        },

        {
          path: 'programs',
          canActivate: [AuthGuard(['Admin'])],
          loadChildren: () => import('./pages/program/programs.routes')
            .then((mod) => mod.programsRoutes)
        },

        // { path: 'courses',
        //   canActivate: [AuthGuard(['Admin'])],
        //   loadChildren: () => import('./pages/courses/courses.routes')
        //     .then((mod) => mod.coursesRoutes)
        // },

        { path: 'curricula',
          canActivate: [AuthGuard(['Admin'])],
          loadChildren: () => import('./pages/curriculum/curricula.routes')
            .then((mod) => mod.curriculaRoutes)
        },

        { path : 'students',
          canActivate: [AuthGuard(['Admin', 'Staff'])],
          loadChildren: () => import('./pages/students/students.routes')
            .then((mod) => mod.studentRoutes),
        },

        { path: 'school-year',
          canActivate: [AuthGuard(['Admin'])],
          loadChildren: () => import('./pages/school-year/school-year.routes')
            .then(mod => mod.schoolYearRoutes)
        },
        {
          path: 'semester-management',
          canActivate: [AuthGuard(['Admin'])],
          loadChildren: () => import('./pages/school-year/semester-management.routes')
            .then(mod => mod.semesterRoutes)
        },

        { path: 'course-availability',
          canActivate: [AuthGuard(['Admin', 'Staff'])],
          loadChildren: () => import('./pages/courses/courses.availability.routes')
            .then(mod => mod.courseAvailabilityRoutes),
        },
      ]
    },

    {
      path: 'login',
      component: LoginUiComponent,
      canActivate: [AnonGuard]
    },

    {
      path: "**", component: ErrorPageComponent
    }

];
