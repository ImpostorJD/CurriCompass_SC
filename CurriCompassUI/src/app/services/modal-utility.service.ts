import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class ModalUtilityService {

  constructor() { }

  modalEnabled: boolean = false;
  selectedItem: number = 0;

  enableModal(id: number){
    this.modalEnabled = true;
    this.selectedItem = id;
  }

  disableModal(){
    this.modalEnabled = false;
    this.selectedItem = 0;
  }

}
