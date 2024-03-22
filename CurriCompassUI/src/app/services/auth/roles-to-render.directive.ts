import { Directive, Input, TemplateRef, ViewContainerRef, inject } from '@angular/core';
import { AuthService } from '../auth.service';


/**
 * This is used to provide conditional rendering depending on the role of the user
 */
@Directive({
  selector: '[appRolesToRender]',
  standalone: true
})
export class RolesToRenderDirective {
  constructor(
    private templateRef: TemplateRef<any>,
    private viewContainer: ViewContainerRef,
  ) {}
  private authService: AuthService = inject(AuthService);

  /**
   * Usage: <div *appRolesToRender="['Role 1', 'Role 2']"> </div>
   */
  @Input('appRolesToRender') allowedRoles: string[] = [];

  async ngOnInit(){
    const user = await this.authService.getUser();
    const roleInstance = user.user_roles;
    for(let role of this.allowedRoles) {

      for(let userRole of roleInstance) {
        if (role.includes(userRole.rolename)){
          this.viewContainer.createEmbeddedView(this.templateRef);
          return;
        }
      }
    }

    this.viewContainer.clear();

  }
}
