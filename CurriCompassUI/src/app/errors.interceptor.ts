import { HttpErrorResponse, HttpEvent, HttpHandler, HttpHandlerFn, HttpInterceptorFn, HttpRequest } from '@angular/common/http';
import { throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
/**
 * 3/1/2024
 *
 * To be provided as an interceptor to redirect the user to appropriate error page.
 * TODO: Implement interceptors
 *
 * @author John Daniel Tejero
 * @param req
 * @param next
 * @returns
 */

export const errorsInterceptor: HttpInterceptorFn = (req: HttpRequest<any>, next: HttpHandlerFn) => {
  return next(req).pipe(
    catchError((error: HttpEvent<any>) => {
      // Handle errors here
      if (error instanceof HttpErrorResponse) {
        console.error('HTTP error:', error);

        // Customize error handling based on status code, request type, etc.
        if (error.status === 401) {
          // Handle unauthorized errors, e.g., redirect to login
        } else if (error.status === 404) {
          // Handle not found errors, e.g., display a user-friendly message
        } else {
          // Handle generic errors, e.g., display an error banner
        }

        // You can also return a custom observable for further handling
        return throwError(error);
      } else {
        // Handle other errors
        console.error('Unexpected error:', error);
        return throwError(error);
      }
    })
  );
};
