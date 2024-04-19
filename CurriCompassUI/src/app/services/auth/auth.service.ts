import { Inject, Injectable, PLATFORM_ID, inject } from '@angular/core';
import { Observable, lastValueFrom } from 'rxjs';
import { isPlatformBrowser } from '@angular/common';
import { HttpReqHandlerService } from '../http-req-handler.service';
import { httpOptions } from '../../../configs/Constants';


/**
 * 3/1/2024
 *
 * Service for handling authentication context for stateless REST API services
 */
@Injectable({
  providedIn: 'root'
})
export class AuthService {

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    @Inject(HttpReqHandlerService) private req: HttpReqHandlerService,
  ) { }

  private currentUser:any = null;

  /**
   * function for authenticating user
   *
   * @param email
   * @param password
   * @returns
   */
  login(email: string, password: string) : Observable<any> {
    return this.req.postResource('users/login', {email: email, password: password}, {});
  }

  /**
   * function for invalidating login context
   * @returns
   */
  logout() :Observable<any> {
    // Send a logout request to your backend to clear the HttpOnly cookie
    return this.req.getResource('users/logout', httpOptions(this.getCookie('user')));
  }
  /**
   * function for setting cookie for the JWT
   *
   * @returns void
   */
  setCookie(ckey:string ,cvalue:string, exdays:number = 7) {
    if (isPlatformBrowser(this.platformId)) {
      const d = new Date();
      d.setTime(d.getTime() + (exdays*24*60*60*1000));
      let expires = "expires=" + d.toUTCString();
      document.cookie = ckey + "=" + cvalue + ";" + expires + ";path=/";
      return;
    }
  }

  /**
   * function for retrieving cookie for the JWT
   *
   * @returns string
   */
  getCookie(ckey:string) {
      if (isPlatformBrowser(this.platformId)) {
        let key:string = ckey + "=";
        let decodedCookie:string = decodeURIComponent(document.cookie);
        let cookieAttributes:string[] = decodedCookie.split(';');
        for(let i = 0; i < cookieAttributes.length; i++) {
          let cookie = cookieAttributes[i];
          while (cookie.charAt(0) == ' ') {
            cookie = cookie.substring(1);
          }
          if (cookie.indexOf(key) == 0) {
            return cookie.substring(key.length, cookie.length);
          }
        }
      }
      return "";
    }

    /**
     * function for deleting cookie for the JWT
    *
    * @param ckey key for the cookie
    * @returns void
    */
   deleteCookie(ckey:string){
     if(isPlatformBrowser(this.platformId)){
       if(this.getCookie(ckey)){
         document.cookie = ckey + "=;expires="+ 0 + ";path=/"
        }
        return;
      }
  }

  async checkUserAsync(): Promise<any> {
    return await lastValueFrom(this.req.getResource('users/profile', httpOptions(this.getCookie('user'))));
  }

  checkUser(): Observable<any>{
    return this.req.getResource('users/profile', httpOptions(this.getCookie('user')));
  }

  removeUserContext(){
    this.currentUser = null;
  }

  async getUser() {
    if(this.currentUser == null){
      const resp = await this.checkUserAsync();
      this.currentUser = resp[1];
    }
    return this.currentUser
  }
}
