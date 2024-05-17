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

  panelToggled?: boolean;
  initialCheckDone: boolean = false;

  readonly buttons: Array<NavigationItems> = [
    /**Commented out for the sake of demonstration for cap 1: Should I not develop this on time. */
    // {
    //   name : "Dashboard",
    //   allowedRoles: ['Admin', 'Staff', 'Student'],
    //   icon_type: "material-symbols-outlined",
    //   icon: "dashboard",
    //   path: "/",
    //   hoverGroup: 'dashboard',
    // },
    {
      name : "Consulatation",
      allowedRoles: ['Admin','Staff','Student'],
      icon_type: "material-symbols-outlined",
      icon: "explore",
      path: "/consultation",
      hoverGroup: 'consultation',
    },
    {
      name : "Profile",
      allowedRoles: ['Admin', 'Staff', 'Student'],
      icon_type: "material-symbols-outlined",
      icon: "account_circle",
      path: "/profile",
      hoverGroup: 'profile',
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
      name : "Curriculum",
      allowedRoles: ['Admin', 'Staff'],
      icon_type: "material-symbols-outlined",
      icon: "contract",
      path: "/curricula",
      hoverGroup: 'curricula',
    },
    {
      name : "Program",
      allowedRoles: ['Admin', 'Staff'],
      icon_type: "material-symbols-outlined",
      icon: "book",
      path: "/programs",
      hoverGroup: 'programs',
    },
    {
      name : "Courses",
      allowedRoles: ['Admin', 'Staff'],
      icon_type: "material-symbols-outlined",
      icon: "book_5",
      path: "/courses",
      hoverGroup: 'courses',
    },
    {
      name : "School Calendar",
      allowedRoles: ['Admin', 'Staff'],
      icon_type: "material-symbols-outlined",
      icon: "event_note",
      path: "/school-calendar",
      hoverGroup: 'school_calendar',
    },
    {
      name : "Course Availability",
      allowedRoles: ['Admin', 'Staff'],
      icon_type: "material-symbols-outlined",
      icon: "menu_book",
      path: "/course-availability",
      hoverGroup: 'course_availability',
    },

  ];

  ngOnInit(){
    this.panelToggled = true;
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
