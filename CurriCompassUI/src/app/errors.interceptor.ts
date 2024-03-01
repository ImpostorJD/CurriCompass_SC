import { HttpInterceptorFn } from '@angular/common/http';

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
export const errorsInterceptor: HttpInterceptorFn = (req, next) => {
  return next(req);
};
