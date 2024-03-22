import { HttpErrorResponse, HttpEvent, HttpHandlerFn, HttpInterceptorFn, HttpRequest } from '@angular/common/http';
import { throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { AuthService } from '../auth.service';
import { inject } from '@angular/core';
import { Router } from '@angular/router';
/**
 * 3/1/2024
 *
 * To be provided as an interceptor to redirect the user to appropriate error page.
 * TODO: Implement interceptors
 *
 * @param req
 * @param next
 * @returns
 */
export const errorsInterceptor: HttpInterceptorFn = (req: HttpRequest<any>, next: HttpHandlerFn) => {
  const auth: AuthService =  inject(AuthService);
  const router: Router =  inject(Router);

  return next(req).pipe(
    catchError((error: HttpEvent<any>) => {

      // Handle errors here
      if (error instanceof HttpErrorResponse) {
        if (error.status == 401) {
          if(router.url == "login") {
            return next(req);
          }

          auth.deleteCookie('user');
          router.navigateByUrl('/login');

        } else if (error.status === 404) {

          if(router.url == "login") {
            return next(req);
          }
          router.navigateByUrl('/error/404');

        } else if (error.status === 403) {
          if(router.url == "login") {
            return next(req);
          }
          router.navigateByUrl('/error/403');
        } else {
          router.navigateByUrl('/error/500');
        }

        return throwError(() => error);
      }

      return throwError(() => error);

    })

  );

};
