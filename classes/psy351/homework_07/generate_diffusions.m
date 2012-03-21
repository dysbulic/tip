function [diffusions] = generate_diffusions(config)

Ter_lo = config.Ter - config.st / 2;
Ter_hi = config.Ter + config.st / 2;

z_lo = config.z - config.sz / 2;
z_hi = config.z + config.sz / 2;

means = config.nu + config.eta * randn(config.iterations, 1);

diffusions = {};

for i = 1:config.iterations
    TR =  Ter_lo + rand * (Ter_hi - Ter_lo);
    ZZ = z_lo + rand * (z_hi - z_lo);
    diffusions{i} = ...
        diffusion_simulation_path(means(i), config.s2, TR, config.a, ZZ);
end
