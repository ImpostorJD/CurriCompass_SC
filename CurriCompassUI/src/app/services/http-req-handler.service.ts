import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '../../configs/Config';
import { Observable } from 'rxjs';

/**
 * 3/1/2024
 *
 *  This is the default configuration for HTTP requests, processes that requires custom configuration
 *  will be handled through careful Object Key/Value pair configuration
 *
 *  USAGE: No usage as of the moment
 *
 * IMPORTANT NOTE: use proper method for each http method signature to avoid complications
 */
@Injectable({
  providedIn: 'root'
})
export class HttpReqHandlerService {

  constructor(private http: HttpClient) {}

  /**
   * Method for HTTP Get Method
   * @param endpoint uses relative path, e.g. /users/ for getting list of users.
   * @param config usually used for configuring headers, leave as empty object {} if no configuration, otherwise set the configuration via key/value pair
   * @returns Observable object
   */
  getResource(endpoint : string, config : Object) : Observable<HttpReqHandlerService> {
    return this.http.get<HttpReqHandlerService>(environment.apiUrl + endpoint, config);
  }

  /**
   * Method for HTTP POST Method
   * @param endpoint  uses relative path, e.g. /users/ for getting list of users.
   * @param body a key/value pair object containing necessary payloads e.g. {"username" : "test", "password" : "examplepassword"}
   * @param config usually used for configuring headers,leave as empty object {} if no configuration, otherwise set the configuration via key/value pair
   * @returns Observable object
   */
  postResource(endpoint: string, body : Object, config : Object) : Observable<HttpReqHandlerService> {
    return this.http.post<HttpReqHandlerService>(environment.apiUrl + endpoint, body, config);
  }


  /**
   * Method for HTTP PUT  Method
   * @param endpoint  uses relative path, e.g. /users/ for getting list of users.
   * @param body a key/value pair object containing necessary payloads e.g. {"username" : "test", "password" : "examplepassword"}
   * @param config usually used for configuring headers,leave as empty object {} if no configuration, otherwise set the configuration via key/value pair
   * @returns Observable object
   */
  putResource(endpoint: string, body : Object, config : Object) : Observable<HttpReqHandlerService> {
    return this.http.put<HttpReqHandlerService>(environment.apiUrl + endpoint, body, config);
  }


  /**
   * Method for HTTP PATCH Method
   * @param endpoint  uses relative path, e.g. /users/ for getting list of users.
   * @param body a key/value pair object containing necessary payloads e.g. {"username" : "test", "password" : "examplepassword"}
   * @param config usually used for configuring headers,leave as empty object {} if no configuration, otherwise set the configuration via key/value pair
   * @returns Observable object
   */
  patchResource(endpoint : string, body : Object, config : Object) : Observable<HttpReqHandlerService> {
    return this.http.patch<HttpReqHandlerService>(environment.apiUrl + endpoint, body, config);
  }


  /**
   * Method for HTTP DELETE Method
   * @param endpoint  uses relative path, e.g. /users/ for getting list of users.
   * @param body a key/value pair object containing necessary payloads e.g. {"username" : "test", "password" : "examplepassword"}
   * @param config usually used for configuring headers,leave as empty object {} if no configuration, otherwise set the configuration via key/value pair
   * @returns Observable object
   */
  deleteResource(endpoint : string, config : Object) : Observable<HttpReqHandlerService> {
    return this.http.delete<HttpReqHandlerService>(environment.apiUrl + endpoint, config);
  }
}
