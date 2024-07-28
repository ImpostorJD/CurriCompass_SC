import { CommonModule } from '@angular/common';
import { Component, Inject, inject } from '@angular/core';
import NavigationItems from '../../models/navigation-items';
import { Router, RouterLink, RouterLinkActive } from '@angular/router';
import { DOCUMENT } from '@angular/common';

import { RolesToRenderDirective } from '../../services/auth/roles-to-render.directive';
import { AuthService } from '../../services/auth/auth.service';
@Component({
  selector: 'app-navigation',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    RouterLinkActive,
    RolesToRenderDirective
  ],
  templateUrl: './navigation.component.html',
  styleUrl: './navigation.component.css'
})
export class NavigationComponent {
  constructor(
    @Inject(DOCUMENT) private document: Document,
    private router: Router,
    ) {}

  private auth: AuthService = inject(AuthService);
  currentUser: any = null;;

  panelToggled?: boolean;
  initialCheckDone: boolean = false;

  readonly buttons: Array<NavigationItems> = [

    {
      name : "Academic Advising",
      allowedRoles: ['Admin','Staff','Student'],
      icon_type: "material-symbols-outlined",
      icon: "explore",
      path: "/",
      hoverGroup: 'consultation',
    },
    {
      name : "Change Password",
      allowedRoles: ['Admin','Staff','Student'],
      icon_type: "material-symbols-outlined",
      icon: "key",
      path: "/users/change-password",
      hoverGroup: 'changepass',
    },
    {
      name : "Users",
      allowedRoles: ['Admin'],
      icon_type: "material-symbols-outlined",
      icon: "groups",
      path: "/users",
      hoverGroup: 'users',
    },
    {
      name : "Student Records",
      allowedRoles: ['Admin', 'Staff'],
      icon_type: "material-symbols-outlined",
      icon: "person_book",
      path: "/students",
      hoverGroup: 'students',
    },
    {
      name : "Pending",
      allowedRoles: ['Admin', 'Staff'],
      icon_type: "material-symbols-outlined",
      icon: "person_alert",
      path: "/users/pending-students",
      hoverGroup: 'pending-students',
    },
    {
      name : "Course Availability",
      allowedRoles: ['Admin', 'Staff'],
      icon_type: "material-symbols-outlined",
      icon: "menu_book",
      path: "/course-availability",
      hoverGroup: 'course_availability',
    },
    {
      name : "Curriculum",
      allowedRoles: ['Admin'],
      icon_type: "material-symbols-outlined",
      icon: "contract",
      path: "/curricula",
      hoverGroup: 'curricula',
    },
    {
      name : "Program",
      allowedRoles: ['Admin'],
      icon_type: "material-symbols-outlined",
      icon: "book",
      path: "/programs",
      hoverGroup: 'programs',
    },
    // {
    //   name : "Courses",
    //   allowedRoles: ['Admin'],
    //   icon_type: "material-symbols-outlined",
    //   icon: "book_5",
    //   path: "/courses",
    //   hoverGroup: 'courses',
    // },
    {
      name : "School Year",
      allowedRoles: ['Admin'],
      icon_type: "material-symbols-outlined",
      icon: "event_note",
      path: "/school-year",
      hoverGroup: 'school_year',
    },
    {
      name : "Semester Management",
      allowedRoles: ['Admin'],
      icon_type: "material-symbols-outlined",
      icon: "calendar_view_month",
      path: "/semester-management",
      hoverGroup: 'school_calendar',
    },
    // {
    //   name : "Profile",
    //   allowedRoles: ['Admin', 'Staff', 'Student'],
    //   icon_type: "material-symbols-outlined",
    //   icon: "account_circle",
    //   path: "/profile",
    //   hoverGroup: 'profile',
    // },

  ];

  async ngOnInit(){
    this.panelToggled = true;
    this.currentUser = await this.auth.getUser();
  }

  ngDoCheck(){
    if (!this.initialCheckDone && this.document.defaultView && this.document.defaultView.innerWidth < 768) {
      this.panelToggled = false;
      this.initialCheckDone = true;
    }else{
      this.initialCheckDone = false;
    }
  }

  collapse(){
    this.panelToggled = !this.panelToggled;
  }

  logout(){
    this.auth.logout().subscribe({
      next: ()=> {
        this.auth.deleteCookie('user');
        this.auth.removeUserContext();
        this.router.navigate(['/login']);
      },
      error: err => console.error(err),
    });
  }
}
