import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AddCourseAvailabilityComponent } from './add-course-availability.component';

describe('AddCourseAvailabilityComponent', () => {
  let component: AddCourseAvailabilityComponent;
  let fixture: ComponentFixture<AddCourseAvailabilityComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AddCourseAvailabilityComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(AddCourseAvailabilityComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
