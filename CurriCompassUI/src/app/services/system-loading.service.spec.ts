import { TestBed } from '@angular/core/testing';

import { SystemLoadingService } from './system-loading.service';

describe('SystemLoadingService', () => {
  let service: SystemLoadingService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(SystemLoadingService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
