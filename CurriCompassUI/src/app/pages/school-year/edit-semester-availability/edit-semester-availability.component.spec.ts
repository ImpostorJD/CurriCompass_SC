import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditSemesterAvailabilityComponent } from './edit-semester-availability.component';

describe('EditSemesterAvailabilityComponent', () => {
  let component: EditSemesterAvailabilityComponent;
  let fixture: ComponentFixture<EditSemesterAvailabilityComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [EditSemesterAvailabilityComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(EditSemesterAvailabilityComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
