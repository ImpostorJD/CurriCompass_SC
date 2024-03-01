import { HttpHeaders } from "@angular/common/http";

/**
 * 3/1/2024
 *
 * Constants file
 * This is mostly used to configure authorization header, this is the default setting and can still be overridden
 *
 * @author John Daniel Tejero
 * @param authToken
 */
export const httpOptions = (authToken: string) => {
  headers: new HttpHeaders({
  'Authorization': `Bearer ${authToken}`,
  'Content-Type': 'application/json',
})}

