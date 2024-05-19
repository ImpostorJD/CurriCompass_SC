import { TestBed } from '@angular/core/testing';

import { ModalUtilityService } from './modal-utility.service';

describe('ModalUtilityService', () => {
  let service: ModalUtilityService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ModalUtilityService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
