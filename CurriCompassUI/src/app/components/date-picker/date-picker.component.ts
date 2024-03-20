import { CommonModule } from '@angular/common';
import { Component, EventEmitter, Input, Output } from '@angular/core';
import { FormsModule } from '@angular/forms';
import moment  from 'moment';

@Component({
  selector: 'app-date-picker',
  standalone: true,
  imports: [
    FormsModule,
    CommonModule
  ],
  templateUrl: './date-picker.component.html',
  styleUrl: './date-picker.component.css'
})
export class DatePickerComponent {

  @Output('yearInput') yearInput = new EventEmitter<string>();
  @Input('yearData') yearData: any;

  MONTH_NAMES = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
  ];
  DAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
  days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

  showDatepicker = false;
  datepickerValue!: string;
  month!: number;
  year!: number;
  no_of_days = [] as number[];
  blankdays = [] as number[];

  constructor() {}


  initDate() {
    let today = new Date();
    this.month = today.getMonth();
    this.year = today.getFullYear();
    const dateValue = new Date(this.year, this.month, today.getDate());
    this.datepickerValue =  moment(dateValue).format('YYYY/MM/DD');
    this.emitEvent();
  }

  emitEvent(){
    this.yearInput.emit(this.datepickerValue);
  }

  isToday(date: any) {
    const today = new Date();
    const d = new Date(this.year, this.month, date);
    return today.toDateString() === d.toDateString() ? true : false;
  }

  getDateValue(date: any) {
    let selectedDate = new Date(this.year, this.month, date);
    this.datepickerValue =  moment(selectedDate).format('YYYY/MM/DD');
    this.showDatepicker = false;
    this.emitEvent();
  }

  goToNextMonth() {
    this.month = this.month + 1;
    if(this.month > 11){
      this.month = 0;
      this.year = this.year + 1;
    }
    this.getNoOfDays();
  }

  goToPreviousMonth() {
    this.month = this.month - 1;
    if(this.month < 0){
      this.month = 11;
      this.year = this.year - 1;
    }
    this.getNoOfDays();
  }
  getNoOfDays() {
    const daysInMonth = new Date(this.year, this.month + 1, 0).getDate();

    // find where to start calendar day of week
    let dayOfWeek = new Date(this.year, this.month).getDay();
    let blankdaysArray = [];
    for (var i = 1; i <= dayOfWeek; i++) {
      blankdaysArray.push(i);
    }

    let daysArray = [];
    for (var i = 1; i <= daysInMonth; i++) {
      daysArray.push(i);
    }

    this.blankdays = blankdaysArray;
    this.no_of_days = daysArray;
  }

  trackByIdentity = (index: number, item: any) => item;

  ngOnInit() {
    const inputData = new Date(this.yearData);
    if(this.yearData != null && typeof this.yearData !== "undefined"){
      this.datepickerValue =  moment(inputData).format('YYYY/MM/DD');
      this.month =inputData.getMonth();
      this.year = inputData.getFullYear();
    }else{
      this.initDate();
    }

    this.getNoOfDays();
  }

}
