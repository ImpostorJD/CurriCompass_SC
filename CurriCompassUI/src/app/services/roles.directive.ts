import { Directive, Input, TemplateRef, ViewContainerRef } from '@angular/core';
import { AuthService } from './auth.service';
import { Subscription } from 'rxjs';

/**
 * To be used for rendering proper templates per role
 * TODO: fix the code in the subscription
 */
@Directive({
  selector: '[appRoles]',
  standalone: true
})
export class RolesDirective {

  constructor(
    private templateRef: TemplateRef<any>,
    private viewContainer: ViewContainerRef,
    private authService: AuthService
    ) {
      this.subscription = this.authService.checkUser().subscribe((resp) => {
        const roleInstance = resp?.role?.roleName;

        if(this.allowedRoles.includes(roleInstance)) {
          this.viewContainer.createEmbeddedView(this.templateRef)
        }else{
          this.viewContainer.clear();
        }
      });
    }

  @Input('appRoles') allowedRoles: string[] = [];
  private subscription: Subscription;

   ngOnDestroy() {
      this.subscription.unsubscribe();
    }
}
