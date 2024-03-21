import { inject } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivateFn, Router, RouterStateSnapshot, UrlTree } from '@angular/router';
import { AuthService } from '../auth.service';
import { Observable } from 'rxjs';

/**
 * 3/1/2024
 * Middleware to intercept requests to validate client with no login context.
 *
 * Usage: { path: '</url path>', component: </Component>, canActivate: AnonGuard()},
 */
export const AnonGuard: CanActivateFn = (
  route: ActivatedRouteSnapshot,
  state: RouterStateSnapshot
):
  Observable<boolean | UrlTree>
  | Promise<boolean | UrlTree>
  | boolean
  | UrlTree => {

  return inject(AuthService).getCookie('user').length == 0
    ? true
    : inject(Router).navigateByUrl('/');

};
