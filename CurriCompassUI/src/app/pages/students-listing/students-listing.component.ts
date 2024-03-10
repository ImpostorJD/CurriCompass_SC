import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-students-listing',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './students-listing.component.html',
  styleUrl: './students-listing.component.css'
})
export class StudentsListingComponent {

}
