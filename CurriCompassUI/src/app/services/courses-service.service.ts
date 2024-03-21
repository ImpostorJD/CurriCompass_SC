import { Injectable, inject } from '@angular/core';
import { HttpReqHandlerService } from './http-req-handler.service';
import { httpOptions } from '../../configs/Constants';
import { catchError, map, of } from 'rxjs';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root',
})
export class CoursesServiceService {
  constructor() {}

  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);

  getCourses() {
    return this.req.getResource('subjects', httpOptions(this.auth.getCookie('user')))
      .pipe(
        map((res: any) => res[1]),
        catchError(err => {
          console.error(err);
          return of([]);
        })
      );
  }

  getCourse(index: number) {
    return this.req.getResource('subjects/' + index, httpOptions(this.auth.getCookie('user')))
    .pipe(
      map((res: any) => res[1]),
      catchError(err => {
        console.error(err);
        return of(null);
      })
    );
  }
}
