import { Injectable, inject } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, CanActivateFn, Router, RouterStateSnapshot, UrlTree } from '@angular/router';
import { AuthService } from './auth.service';
import { Observable } from 'rxjs';

/**
 * 3/1/2024
 * Middleware to intercept requests to validate client with no login context.
 *
 * Usage: { path: '</url path>', component: </Component>, canActivate: AnonGuard()},
 * @author John Daniel Tejero
 */
export const AnonGuard: CanActivateFn = (
  route: ActivatedRouteSnapshot,
  state: RouterStateSnapshot
):
  Observable<boolean | UrlTree>
  | Promise<boolean | UrlTree>
  | boolean
  | UrlTree=> {

  return inject(AuthService).getCookie('user') != null
    ? true
    : inject(Router).createUrlTree(['/']);

};
