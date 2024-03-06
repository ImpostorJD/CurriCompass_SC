import { ActivatedRouteSnapshot, CanActivateFn, RouterStateSnapshot, UrlTree } from '@angular/router';
import { AuthService } from './auth.service';
import { inject } from '@angular/core';
/**
 * 3/1/2024
 * Middleware to intercept requests to validate login context.
 *
 * Usage: { path: '</url path>', component: </Component>, canActivate: AuthGuard(["roles_array"])},
 * TODO: Configure error handling
 */

export function AuthGuard(allowedRoles: string[]): CanActivateFn {
  return (route: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean | UrlTree => {

    const getUserCookie = inject(AuthService).getCookie('user');
    try{

      if (allowedRoles.length > 0) {
        const user:any = inject(AuthService).checkUserAsync();

        for(let role of allowedRoles) {

          for(let userRole of user.roles) {
            if (role.includes(userRole)){
              return true;
            }

          }

        }
        return false;
      }

    }catch(e){
      console.log('Unhandled error:', e);
    }
    return getUserCookie != null;
  };
}
