import { Injectable } from '@angular/core';
import moment from 'moment';

@Injectable({
  providedIn: 'root'
})
export class FormatDateService {

  constructor() { }
  /**
   * March 3/21/24
   *
   * To transform date into human readable to year
   * @param date
   *
   * @returns string of formatted date
   */
  formatToYear(date: Date){
    return moment(date).format('YYYY');
  }

}
