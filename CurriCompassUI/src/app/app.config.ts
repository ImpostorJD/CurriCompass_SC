import { ApplicationConfig } from '@angular/core';
import { provideRouter } from '@angular/router';

import { routes } from './app.routes';
import { provideClientHydration } from '@angular/platform-browser';
import { provideAnimations } from '@angular/platform-browser/animations';
import { provideHttpClient, withFetch, withInterceptors } from '@angular/common/http';
import { errorsInterceptor } from './services/interceptors/errors.interceptor';
import { HttpReqHandlerService } from './services/http-req-handler.service';
import { AuthService } from './services/auth/auth.service';

export const appConfig: ApplicationConfig = {
  providers: [
    HttpReqHandlerService,
    AuthService,
    provideRouter(routes),
    provideClientHydration(),
    provideAnimations(),
    provideHttpClient(
      withFetch(),
      withInterceptors([
        errorsInterceptor,
      ]),
    ),
  ]
};
