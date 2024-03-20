import { CommonModule } from '@angular/common';
import { Component, Inject } from '@angular/core';
import { RolesDirective } from '../../services/roles.directive';
import NavigationItems from '../../models/navigation-items';
import { Router, RouterLink, RouterLinkActive } from '@angular/router';
import { DOCUMENT } from '@angular/common';
import { AuthService } from '../../services/auth.service';
@Component({
  selector: 'app-navigation',
  standalone: true,
  imports: [
    CommonModule,
    RolesDirective,
    RouterLink,
    RouterLinkActive
  ],
  templateUrl: './navigation.component.html',
  styleUrl: './navigation.component.css'
})
export class NavigationComponent {
  constructor(
    @Inject(DOCUMENT) private document: Document,
    private router: Router,
    private auth: AuthService,
  ) {}

  panelToggled?: boolean;
  initialCheckDone: boolean = false;

  //TODO: add appropriate nav items
  readonly buttons: Array<NavigationItems> = [
    {
      name : "Dashboard",
      allowedRoles: [],
      icon_type: "material-symbols-outlined",
      icon: "dashboard",
      path: "/"
    },
    {
      name : "Profile",
      allowedRoles: [],
      icon_type: "material-symbols-outlined",
      icon: "account_circle",
      path: "/profile"
    },
    {
      name : "Users",
      allowedRoles: [],
      icon_type: "material-symbols-outlined",
      icon: "groups",
      path: "/users"
    },
    {
      name : "Student Records",
      allowedRoles: [],
      icon_type: "material-symbols-outlined",
      icon: "person_book",
      path: "/students"
    },
    {
      name : "Curriculum",
      allowedRoles: [],
      icon_type: "material-symbols-outlined",
      icon: "contract",
      path: "/curricula"
    },
    {
      name : "Program",
      allowedRoles: [],
      icon_type: "material-symbols-outlined",
      icon: "book",
      path: "/programs"
    },
    {
      name : "Courses",
      allowedRoles: [],
      icon_type: "material-symbols-outlined",
      icon: "menu_book",
      path: "/courses"
    },
    {
      name : "School Calendar",
      allowedRoles: [],
      icon_type: "material-symbols-outlined",
      icon: "event_note",
      path: "/school-calendar"
    },
    {
      name : "Consulatation",
      allowedRoles: [],
      icon_type: "material-symbols-outlined",
      icon: "explore",
      path: "/consulatation"
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
    this.auth.deleteCookie('user')
    this.router.navigate(['/login'])
  }
}
