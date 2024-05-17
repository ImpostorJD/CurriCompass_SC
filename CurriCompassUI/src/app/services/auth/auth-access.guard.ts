import { ActivatedRouteSnapshot, CanActivateFn, Router, RouterStateSnapshot, UrlTree } from '@angular/router';
import { inject } from '@angular/core';
import { AuthService } from './auth.service';
/**
 * 3/1/2024
 * Middleware to intercept requests to validate login context.
 *
 * Usage: { path: '</url path>', component: </Component>, canActivate: AuthGuard(["roles_array"])},
 */

export function AuthGuard(allowedRoles: string[]): CanActivateFn {
  return async (route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Promise<boolean | UrlTree>  => {

    const getUserCookie = inject(AuthService).getCookie('user');
    try{
      const resp:any = await inject(AuthService).getUser();

      if (allowedRoles.length > 0) {
        const user = await resp[1];
        if (user){
          for(let role of allowedRoles) {

            for(let userRole of user!.user_roles) {
              if (role.includes(userRole.rolename)){
                return true;
              }
            }
          }
        }

        inject(Router).navigateByUrl('/');
      }

    }catch(_){
      /**
       * BUG: auth_access_guard not working properly (an admin logged cannot view admin dashboard)
       * <Description>
       *  when try catch is not implemented, the inject() throws an error as it should be declare in the constructor
       *  however the function has no default constructor.
       *
       * </Description>
       */
    }
    return getUserCookie != null;
  };
}
