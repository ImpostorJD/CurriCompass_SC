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
          path: '', redirectTo: 'consultation', pathMatch:'full'
        },

        {
          path : 'profile',
          loadComponent : () => import('./pages/users/profile-page/profile-page.component')
            .then((mod) => mod.ProfilePageComponent)
        },

        { path: 'users',
          canActivate: [AuthGuard(['Admin'])],
          loadChildren: () => import('./pages/users/users.routes')
            .then((mod) => mod.usersRoutes)
        },

        {
          path: 'programs',
          canActivate: [AuthGuard(['Admin'])],
          loadChildren: () => import('./pages/program/programs.routes')
            .then((mod) => mod.programsRoutes)
        },

        { path: 'courses',
          canActivate: [AuthGuard(['Admin'])],
          loadChildren: () => import('./pages/courses/courses.routes')
            .then((mod) => mod.coursesRoutes)
        },

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

        { path: 'school-calendar',
          canActivate: [AuthGuard(['Admin', 'Staff'])],
          loadChildren: () => import('./pages/school-year/school-year.routes')
            .then(mod => mod.schoolYearRoutes)
        },

        { path: 'course-availability',
          canActivate: [AuthGuard(['Admin', 'Staff'])],
          loadChildren: () => import('./pages/courses/courses.availability.routes')
            .then(mod => mod.courseAvailabilityRoutes),
        },

        {
          path: "consultation",
          loadChildren: () => import('./pages/consultation/consultation.routes')
            .then(mod => mod.consultationRoutes)
        }
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
