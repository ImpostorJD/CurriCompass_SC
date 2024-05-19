import { CommonModule } from '@angular/common';
import { Component, EventEmitter, Input, Output } from '@angular/core';
import { RouterLink } from '@angular/router';
import { CourseFilterPipe } from '../../services/filter/search-filters/course-pipe.pipe';
import { FormsModule } from '@angular/forms';
import { CoursesServiceService } from '../../services/courses-service.service';


@Component({
  selector: 'app-delete-modal',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    CourseFilterPipe,
    FormsModule,],
  templateUrl: './delete-modal-popup.component.html',
  styleUrl: './delete-modal-popup.component.css'
})

export class DeleteModalPopupComponent {
  constructor(){}

  @Input("item") item!: number;
  @Output("deleteItem") deleteItem = new EventEmitter<number>()
  @Output("collapseModal") collapseModal = new EventEmitter<boolean>()

  deleteItemEvent(){
    this.deleteItem.emit(this.item);
  }

  collapseModalEvent(){
    this.collapseModal.emit(true);
  }
}
