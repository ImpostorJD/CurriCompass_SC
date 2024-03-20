import { Injectable } from '@angular/core';
import moment from 'moment';

@Injectable({
  providedIn: 'root'
})
export class FormatDateService {

  constructor() { }
  formatToYear(date: Date){
    return moment(date).format('YYYY');
  }

}
