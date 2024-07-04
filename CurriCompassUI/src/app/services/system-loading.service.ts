import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class SystemLoadingService {

  constructor() { }

  loading = false;

  initLoading() {
    this.loading = true;
  }

  endLoading() {
    this.loading = false;
  }
}
