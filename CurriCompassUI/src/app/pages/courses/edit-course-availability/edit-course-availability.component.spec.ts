import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditCourseAvailabilityComponent } from './edit-course-availability.component';

describe('EditCourseAvailabilityComponent', () => {
  let component: EditCourseAvailabilityComponent;
  let fixture: ComponentFixture<EditCourseAvailabilityComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [EditCourseAvailabilityComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(EditCourseAvailabilityComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
